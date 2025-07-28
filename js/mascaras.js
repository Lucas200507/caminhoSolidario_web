document.addEventListener("DOMContentLoaded", () => {
    const cpf = document.getElementById("cpf");
    if (cpf) {
        cpf.addEventListener('input', function () {
            let valor = this.value.replace(/[^0-9]/g, '');
            if (valor.length > 3 && valor.length <= 6) {
                valor = valor.slice(0, 3) + '.' + valor.slice(3);
            } else if (valor.length > 6 && valor.length <= 9) {
                valor = valor.slice(0, 3) + '.' + valor.slice(3, 6) + '.' + valor.slice(6);
            } else if (valor.length > 9) {
                valor = valor.slice(0, 3) + '.' + valor.slice(3, 6) + '.' + valor.slice(6, 9) + '-' + valor.slice(9, 11);
            }
            this.value = valor;
        });
    }

    const nome = document.getElementById('nome');
    if (nome) {
        nome.addEventListener('input', function () {
            this.value = this.value.replace(/[0-9]/g, '');
        });
    }

    const telefone = document.getElementById("telefone");
    if (telefone) {
        telefone.addEventListener('input', function () {
            let valor2 = this.value.replace(/[^0-9]/g, '');
            if (valor2.length > 2 && valor2.length <= 7) {
                valor2 = '(' + valor2.slice(0, 2) + ') ' + valor2.slice(2);
            } else if (valor2.length > 7) {
                valor2 = '(' + valor2.slice(0, 2) + ') ' + valor2.slice(2, 7) + '-' + valor2.slice(7, 11);
            }
            this.value = valor2;
        });
    }

    const cep = document.getElementById("cep");
    if (cep) {
        cep.addEventListener('input', function () {
            let valor = this.value.replace(/[^0-9]/g, '');
            if (valor.length > 5) {
                valor = valor.slice(0, 5) + '-' + valor.slice(5, 8);
            }
            this.value = valor;
        });
    }

    const comorbidade = document.getElementById("comorbidade");
    if (comorbidade) {
        comorbidade.addEventListener('input', function () {
            this.value = this.value.replace(/[0-9]/g, '');
        });
    }

    const cidade = document.getElementById("cidade");
    if (cidade) {
        cidade.addEventListener('input', function () {
            this.value = this.value.replace(/[0-9]/g, '');
        });
    }

    // SOMENTE NÚMEROS
    const valor = document.getElementById("valor");
    if (valor) {
        valor.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    const valorB = document.getElementById("valorB");
    if (valorB) {
        valorB.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    const quantos_dependentes = document.getElementById("quantos_dependentes");
    if (quantos_dependentes) {
        quantos_dependentes.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    const quantos_trabalham = document.getElementById("quantos_trabalham");
    if (quantos_trabalham) {
        quantos_trabalham.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    const renda_familiar = document.getElementById("renda_familiar");
    if (renda_familiar) {
        renda_familiar.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

});
