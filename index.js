const express = require('express');
const res = require('express/lib/response');

const server = express();

server.use(expree.json);

const cursos = ['Fullstack Master', 'Game Dev', 'Youtube'];

//retorna um curso

server.get('/cursos/:index', (req,res_ => {
    const { index } = req.params;

    return res.json(cursos[index]);
}));

//retornar todos os cursos
server.get('/cursos', (req, res) => {
    return res.json(curso);
});

//criando um novo curso
server.post('/cursos', (req, res) => {
    const {name} = req.body;
    cursos.push(name);

    return res.json(cursos);
})


//atualizar curso
server.put('/cursos/:index', (req,res) => {
    const { index } = req.params;
    const { name } = req.body;
    
    cursos[index] = name;

    return res.json(cursos)
})

//deletar curso
server.delete('/cursos:index', (req, res) => {
    const { index } = req.params;

    cursos.slice(index, 1);
    return res.json( {message: "o curso foi deletado"} );
})



server.listen(3000);