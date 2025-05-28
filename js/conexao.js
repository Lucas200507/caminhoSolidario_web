// Importando os módulos necessários
const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser'); // Para fazer alterações no próprio html
const cors = require('cors');

const app = express();

// Configurando o CORS e o parser JSON
app.use(cors());
app.use(bodyParser.json());

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'senac',
    database: 'caminho_solidario'
});


db.connect(err => {
    if (err) {
        console.error('Erro ao conectar ao banco de dados: ', err);
        return;
    }
    console.log('Conectando no banco de dados');
});

// caminho da api:
// api.get - puxa
// api.read - leiturar
// api.post - manda ou deleta

app.get('/api/pessoa', (req, res) => {
    db.query('SELECT * FROM pessoa', (err, results) => {
        if (err){
            console.error('Erro no banco', err);
            res.status(500).send('Erro ao buscar dados');
            return;
        }
        res.send(results);
    });
});

// INICIANDO O SERVIDOR NA PORTA 3000
const PORT = 3307;
app.listen(PORT, () => {
    console.log(`Servidor rodando em http://localhost:${PORT}`);
})

