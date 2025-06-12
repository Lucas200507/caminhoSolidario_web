# PASSO A PASSO PARA A UTILIZAÇÃO
  BAIXE O BANCO (/BANCO - caminho_solidario/SQL_CaminhoSolidario
  Clone ou baixe o repositório
  Em server.js, altere a conexão do banco:
    const db = mysql.createConnection({
      host: SUA_HOST,
      user: SEU_USER,
      password: SUA_SENHA,
      database: SEU_BANCO,
      port: SUA_PORTA
    })
  Em login.js, altere a url
    const resposta = await fetch('http://localhost:3307/api/login', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          }, 
          body: JSON.stringify({usuario, senha})
      });
  
  
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





