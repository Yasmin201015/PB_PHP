<?php
session_start();

class Aluno {
    private $nome;
    private $sobrenome;
    private $nota;
    private $dataNascimento;

    public function __construct($nome, $sobrenome, $nota, $dataNascimento) {
        $this->nome = $nome;
        $this->sobrenome = $sobrenome;
        $this->nota = $nota;
        $this->dataNascimento = $dataNascimento;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getSobrenome() {
        return $this->sobrenome;
    }

    public function getNota() {
        return $this->nota;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function salvar() {
        if (!isset($_SESSION["alunos"])) {
            $_SESSION["alunos"] = [];
        }
        $_SESSION["alunos"][] = $this;
    }
}

function calcularIdade($dataNascimento) {
    $dataNasc = new DateTime($dataNascimento);
    $hoje = new DateTime();
    return $hoje->diff($dataNasc)->y;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Limpar sessão
    if (isset($_POST["reset"])) {
        session_destroy();
    }

    // Cadastrar aluno
    if (isset($_POST["cadastrar"])) {
        $nome = $_POST["nome"];
        $sobrenome = $_POST["sobrenome"];
        $nota = $_POST["nota"];
        $dataNascimento = $_POST["dataNascimento"];

        $aluno = new Aluno($nome, $sobrenome, $nota, $dataNascimento);
        $aluno->salvar();
    }
}


    if(isset($_GET["reset"])){
        session_destroy();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Alunos</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #000000ff; padding: 10px; text-align: center; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Cadastro de Alunos</h2>

<form method="POST">
    <label>Nome:</label><br>
    <input type="text" name="nome" required><br><br>

    <label>Sobrenome:</label>
    <input type="text" name="sobrenome" required><br><br>

    <label>Nota:</label>
    <input type="number" name="nota" required><br><br>

    <label>Data de Nascimento:</label>
    <input type="date" name="dataNascimento" required><br><br>

    <button type="submit" name="cadastrar">Cadastrar</button>
    <button type="reset">Limpar</button>
</form>

<?php?>
    <table>
        <tr>
            <th>Nome</th>
            <th>Sobrenome</th>
            <th>Nota</th>
            <th>Data de Nascimento</th>
            <th>Idade</th>
        </tr>

        <?php
        $somaNotas = 0;
        foreach ($_SESSION["alunos"] as $aluno):
            $somaNotas += $aluno->getNota();
        ?>
            <tr>
                <td><?= $aluno->getNome() ?></td>
                <td><?= $aluno->getSobrenome() ?></td>
                <td><?= $aluno->getNota() ?></td>
                <td><?= $aluno->getDataNascimento() ?></td>
                <td><?= calcularIdade($aluno->getDataNascimento()) ?></td>
            </tr>
        <?php endforeach; ?>

    </table>

    <p><strong>Média das notas:</strong>
        <?= number_format($somaNotas / count($_SESSION["alunos"]), 2) ?>
    </p>

</body>
</html>
