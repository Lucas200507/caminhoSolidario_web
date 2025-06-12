
import mysql from 'mysql2'

const connection = mysql.createConnection({
    host: 'sql10.freesqldatabase.com',
    user: 'sql10784022',
    password: 'q4xR4emc3R',
    database: 'sql10784022'
});

connection.connect((err) => {
    if (err){
        console.error('Erro ao conectar ao banco: ', err);
    } else {
        console.log('Conectado ao MYSQL');
    }
});

export default connection;