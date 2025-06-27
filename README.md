# PASSO A PASSO PARA A UTILIZAÇÃO
  BAIXE O BANCO (/BANCO - caminho_solidario/SQL_CaminhoSolidario<br>
  Clone ou baixe o repositório<br>
  Em server.js, altere a conexão do banco:<br>
    const db = mysql.createConnection({<br>
      host: SUA_HOST,<br>
      user: SEU_USER,<br>
      password: SUA_SENHA,<br>
      database: SEU_BANCO,<br>
      port: SUA_PORTA<br>
    })<br>
  Em login.js, altere a url<br>
    const resposta = await fetch('http://localhost:3307/api/login', {<br>
          method: 'POST',<br>
          headers: {<br>
              'Content-Type': 'application/json',<br>
          }, 
          body: JSON.stringify({usuario, senha})<br>
      });<br>
  <br>
  <br>
# Revisão de Código e Protótipo - WEB ✍️


  O objetivo desse conteúdo é revisar todos os códigos desenvolvidos no projeto web. 
  A meta final é atingir as boas práticas de programação, garantindo que os códigos estejam organizados, legíveis, padronizados — e que estejam iguais ao protótipo desenvolvido.

⚠️  Aplicar boas práticas de código em projetos web, como HTML, CSS e JavaScript, resulta em código mais legível, fácil de manter, colaborativo e eficiente. Isso envolve seguir padrões de codificação, utilizar ferramentas de formatação, adotar princípios de design como DRY e KISS, e realizar testes abrangentes. 

### ╰┈➤ 📲 Link do Protótipo 

https://j438n7.axshare.com/#id=5rte33&p=paginaservicos

---

 ## 📝 O que foi melhorado:

 ✔️ Organização e Indentação: Organizamos o código de forma clara, com indentação adequada, para facilitar a leitura e a compreensão.

✔️ Utilizamos comentários para explicar a lógica e o propósito dos estilos, facilitando a manutenção e a compreensão do código.

✔️ Utilizamos nomes de identificadores e funções que refletem na sua função, facilitando a compreensão. 

✔️ Melhoramos a ambientação do código.

---

## 📌Considerações Finais

Aplicando as boas práticas, compreendemos que um código limpo é primordial dentro de **qualquer** projeto. É essencial que todos consigam fazer alterações sempre que necessário, e comentários bem explicados é de grande auxilio.

---


## 🫂❤️ Integrantes do Grupo
- Bárbara Letícia Soares Cavalcanti
- Daiana Arruda Rodrigues Santos Ribeiro
- Lucas Ataide Martins





