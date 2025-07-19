// ADICIONA UM EVENTO QUANDO A TELA FOR CARREGADA
document.addEventListener("DOMContentLoaded", () => {
    const cpf = document.getElementById("cpf");
    if (cpf) {
        cpf.addEventListener('input', () => {
            let valor = cpf.value.replace(/[^0-9]/g, '');
            if (valor.length > 3 && valor.length <= 6) {
                valor = valor.slice(0, 3) + '.' + valor.slice(3);
            } else if (valor.length > 6 && valor.length <= 9) {
                valor = valor.slice(0, 3) + '.' + valor.slice(3, 6) + '.' + valor.slice(6);
            } else if (valor.length > 9) {
                valor = valor.slice(0, 3) + '.' + valor.slice(3, 6) + '.' + valor.slice(6, 9) + '-' + valor.slice(9, 11);
            }
            cpf.value = valor;
        });
    }

    const telefone = document.getElementById("telefone");
    if (telefone) {
        telefone.addEventListener('input', () => {
            let valor2 = telefone.value.replace(/[^0-9]/g, '');
            if (valor2.length > 2 && valor2.length <= 7) {
                valor2 = '(' + valor2.slice(0, 2) + ') ' + valor2.slice(2);
            } else if (valor2.length > 7) {
                valor2 = '(' + valor2.slice(0, 2) + ') ' + valor2.slice(2, 7) + '-' + valor2.slice(7, 11);
            }
            telefone.value = valor2;
        });
    }
        // MÁSCARA PARA CEP
        const cep = document.getElementById("cep");
        
        cep.addEventListener('input', () => {
            let valor = cep.value.replace(/[^0-9]/g, '');
            if (valor.length > 5) {
                valor = valor.slice(0, 5) + '-' + valor.slice(5, 8);
            }
            cep.value = valor;
        });
});


