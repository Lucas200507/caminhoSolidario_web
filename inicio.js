// express - framework para criar a API
// mysql2 - driver para conectar com MySQL
// body-parser - para ler JSON do corpo das requisições
// cors - habilita requisições de outros domínios

import express from 'express';
import bodyParser from 'body-parser';
import cors from 'cors';
const app = express();

app.use(cors());
app.use(bodyParser.json());

import rota_usuarios from './routes/usuarios.js';
app.use('/usuarios', rota_usuarios);

app.listen(3000, () => {
    console.log('Servidor rodando na porta 3000')
});