from flask import Flask, jsonify

app = Flask(__name__)

@app.route('/')
def home():
    return jsonify({
        "message": "Halo dari Python!",
        "status": "Server Flask Berjalan Lancar"
    })

if __name__ == '__main__':
    app.run(debug=True, port=5000)