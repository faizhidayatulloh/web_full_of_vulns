from flask import Flask, render_template, request, redirect, session, url_for, make_response
import mysql.connector
from decimal import Decimal
import secrets

app = Flask(__name__)
app.secret_key = 'rahasia123'

# Koneksi ke MariaDB
db = mysql.connector.connect(
    host="localhost",
    user="faiz",
    password="alinda",
    database="bank"
)

# Helper untuk ngecek token
def validate_csrf_token():
    token_cookie = request.cookies.get('csrf_token')
    token_form = request.form.get('csrf_token')
    return token_cookie and token_form and token_cookie == token_form

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        id = request.form['id']
        password = request.form['password']

        cursor = db.cursor(dictionary=True)
        cursor.execute("SELECT * FROM data WHERE id = %s AND password = %s", (id, password))
        user = cursor.fetchone()
        cursor.close()

        if user:
            session['user_id'] = user['id']
            session['nama'] = user['nama']

            resp = make_response(redirect('/'))
            csrf_token = secrets.token_hex(16)
            resp.set_cookie('csrf_token', csrf_token)  # Jangan HttpOnly
            return resp
        else:
            return "Login gagal. ID atau Password salah.", 401

    return render_template('login.html')

@app.route('/')
def index():
    if 'user_id' not in session:
        return redirect(url_for('login'))
    return render_template('index.html', nama=session['nama'])

@app.route('/proses', methods=['POST'])
def proses():
    if 'user_id' not in session:
        return redirect(url_for('login'))

    if not validate_csrf_token():
        return "CSRF token tidak valid", 403

    id = session['user_id']
    aksi = request.form['aksi']
    jumlah = Decimal(request.form['jumlah'])

    cursor = db.cursor(dictionary=True)
    cursor.execute("SELECT * FROM data WHERE id = %s", (id,))
    row = cursor.fetchone()

    if not row:
        return "ID tidak ditemukan", 404

    if aksi == 'tambah':
        saldo_baru = row['jumlah_uang'] + jumlah
    elif aksi == 'tarik':
        if row['jumlah_uang'] < jumlah:
            return "Saldo tidak mencukupi", 400
        saldo_baru = row['jumlah_uang'] - jumlah
    else:
        return "Aksi tidak valid", 400

    cursor.execute("UPDATE data SET jumlah_uang = %s WHERE id = %s", (saldo_baru, id))
    db.commit()
    cursor.close()

    return render_template("result.html", nama=row['nama'], saldo=saldo_baru)

@app.route('/logout')
def logout():
    session.clear()
    resp = make_response(redirect(url_for('login')))
    resp.set_cookie('csrf_token', '', expires=0)  # Hapus token
    return resp

if __name__ == '__main__':
    app.run(debug=True)
