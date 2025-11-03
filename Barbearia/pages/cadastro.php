<?php
$erro = null;
require __DIR__ . '/../includes/header.php';
?>

<div class="container my-5">
    <div class="col-12 col-md-8 col-lg-6 mx-auto">
        <form class="p-4 border rounded-3 bg-body sombra-suave" method="POST" action="cadastro_processa.php" id="formCadastro">
            <h1 class="h3 mb-3 fw-normal text-center">Cadastro de Usuário</h1>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" required>
                <label for="nome">Nome completo</label>
            </div>

            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="dataNascimento" name="data_nascimento" required>
                <label for="dataNascimento">Data de Nascimento</label>
            </div>

            <div class="mb-3">
                <label class="form-label">Sexo:</label>
                <div>
                    <label class="me-3"><input type="radio" name="sexo" value="Masculino" required> Masculino</label>
                    <label><input type="radio" name="sexo" value="Feminino" required> Feminino</label>
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="nomeMaterno" name="nome_materno" placeholder="Nome da mãe" required>
                <label for="nomeMaterno">Nome Materno</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" maxlength="14" required>
                <label for="cpf">CPF</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
                <label for="email">E-mail</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="celular" name="telefone_celular" placeholder="Celular" maxlength="20" required>
                <label for="celular">Telefone Celular</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fixo" name="telefone_fixo" placeholder="Telefone Fixo" maxlength="20">
                <label for="fixo">Telefone Fixo</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" maxlength="9" required>
                <label for="cep">CEP</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Endereço completo" required>
                <label for="endereco">Endereço completo</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="login" name="login" placeholder="Login" required>
                <label for="login">Login</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                <label for="senha">Senha</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="confirmaSenha" name="confirma_senha" placeholder="Confirmar senha" required>
                <label for="confirmaSenha">Confirmar Senha</label>
            </div>

            <div class="d-flex gap-2">
                <button class="w-100 btn btn-success" type="submit">Enviar</button>
                <button class="w-100 btn btn-secondary" type="reset">Limpar Tela</button>
            </div>

            <hr class="my-4">
            <small class="texto-secundario">Já possui cadastro? <a href="login.php">Entrar</a></small>
        </form>
    </div>
</div>

<script>
    function limparNumeros(valor) {
        return valor.replace(/\D/g, '');
    }

    // --- MÁSCARA DE CPF ---
    document.getElementById('cpf').addEventListener('input', function (e) {
        let v = limparNumeros(e.target.value);
  
        if (v.length > 3) {
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
        }
        if (v.length > 6) {
            v = v.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
        }
        if (v.length > 9) {
            v = v.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})$/, '$1.$2.$3-$4');
        }

        e.target.value = v;
    });


    // Telefone Celular (Tava adicionando um monte de "55". Vir aqui se o erro voltar) ---
    document.getElementById('celular').addEventListener('input', function (e) {
        let v_completo = e.target.value;
        let v = limparNumeros(v_completo);
        
        if (v.startsWith('55')) {
            v = v.substring(2);
        }

        v = v.substring(0, 11);

        if (v.length > 0) {
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2'); 
        }
        
        if (v.length > 10) { 
            v = v.replace(/(\d{5})(\d{4})$/, '$1-$2');
        } 
        else if (v.length > 6) {
            v = v.replace(/(\d{4})(\d{4})$/, '$1-$2');
        }
        
        e.target.value = '+55 ' + v;
    });

    // Telefone Fixo (Essa parte é opcional, então não utiliza muito) ---
    document.getElementById('fixo').addEventListener('input', function (e) {
        let v_completo = e.target.value;
        let v = limparNumeros(v_completo);

        if (v.startsWith('55')) {
            v = v.substring(2);
        }

        if (v.length > 0) {
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
        }
        if (v.length > 6) {
             v = v.replace(/(\d{4})(\d{4})$/, '$1-$2'); 
        }

        v = v.substring(0, 10);
        e.target.value = '+55 ' + v;
    });

    document.getElementById('cep').addEventListener('blur', function () {
        let cep = limparNumeros(this.value);
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(resp => resp.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('endereco').value =
                            `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                    }
                });
        }
    });
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>