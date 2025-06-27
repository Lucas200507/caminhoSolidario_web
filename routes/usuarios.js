// CRIANDO ROTA
import express from 'express';
const router = express.Router();
import bd from '../BD.js';

// IMPRIMINDO OS USUÁRIOS
router.get('/', (req, res) => {
    bd.query('SELECT * FROM login', (err, results) => {
        if (err) return res.status(500).json({error: err});
        res.json(results);
        });
});

// CADASTRANDO NOVO USUÁRIO
router.post('/', (req, res) => {
    bd.query('INSERT INTO login (cpf, senha, situacao) VALUES (?, ?, ?)', [cpf, senha, situacao], (err, result) => {
        if (err) return res.status(500).json({error: err});
        res.json({cpf, senha, situacao});
    })
})

export default router;