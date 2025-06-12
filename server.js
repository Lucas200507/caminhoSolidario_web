// express - Framework para criar a API
// mysql2 - Cliente MySQL para NodeJS
// cors - Libera acesso da API por outros domínios
// dotenv - Gerencia variáveis de ambiente

import express from 'express';
import mysql from 'mysql2'
import bodyParser from 'body-parser';
import cors from 'cors';

const app = express();
app.use(cors());
app.use(bodyParser.json());

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'senac',
    database: 'caminho_solidario',
    port: '3307'
})

db.connect(err => {
    if (err){
        console.error('Erro ao conectar ao banco: ', err);
        return;
    } 
    console.log('Conectado com sucesso!!');
});

/*      ----- LOGIN -----       */
/* VERIFICAR O USUARIO (LOGIN) */
/* ENVIAR DADOS DO USUÁRIO (LOGIN E SENHA) */
app.post('/api/login', (req, res) => {
    const {cpf, senha} = req.body;

    db.query('SELECT * FROM login WHERE senha = UPPER(MD5(?)) AND situacao = "V";', [cpf, senha], (err, results) => {
        if (err){
            console.error('Erro ao verificar o usuário - LOGIN ', err);
            res.status(500).send({error: 'Erro no servidor'});
            return;
        } 

        if (results.length > 0){
            res.status(200).send({sucesso: true, usuario: results[0]});
        } else {
            res.status(401).send({sucesso: false, mensagem: 'Usuário ou senha inválidos'});
        }

        
    });
});

const PORT = 3307;
app.listen(PORT, () => {
    console.log(`Servidor rodando na porta: ${PORT}`);
});