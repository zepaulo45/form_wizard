<?php
/**
 * Created by Hox.
 * User: JosePaulo 
 * Date: 22/08/2022
 * Time: 23:39
 */
ob_start();
session_start();

$postData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$action = $postData['action'] ?? 'no-parametrized';
unset($postData['action']);

if(!empty($postData['formSerialize'])){
    parse_str($postData['formSerialize'], $postData);
}

require_once __DIR__ . '/config.php';

$read = new \CRUD\Read;

switch ($action) {
    case 'data-user':

        if(empty($postData['user_document'])){
            $json['error'] = true;
            $json['errorMessage'] = 'Por favor, informe o CPF!';
            break;
        }

        $read->read('users', "WHERE user_document = :document", "document={$postData['user_document']}");
        if($read->getResult()){
            $json['error'] = true;
            $json['errorMessage'] = 'Esse CPF já está em uso, por favor, informe outro!';
            break;
        }

        $_SESSION['wizard']['user_name'] = $postData['user_name'];
        $_SESSION['wizard']['user_lastname'] = $postData['user_lastname'];
        $_SESSION['wizard']['user_document'] = $postData['user_document'];
        $json['success'] = true;
        break;

    case 'data-login':

        if(empty($postData['user_email'])){
            $json['error'] = true;
            $json['errorMessage'] = 'Por favor, informe um e-mail!';
            break;
        }

        if(!filter_var($postData['user_email'], FILTER_VALIDATE_EMAIL)){
            $json['error'] = true;
            $json['errorMessage'] = 'Informe um e-mail válido!';
            break;
        }

        $read->read('users', "WHERE user_email = :email", "email={$postData['user_email']}");
        if($read->getResult()){
            $json['error'] = true;
            $json['errorMessage'] = 'Esse e-mail já está em uso, por favor, informe outro!';
            break;
        }

        $_SESSION['wizard']['user_email'] = $postData['user_email'];
        $_SESSION['wizard']['user_password'] = $postData['user_password'];
        $json['success'] = true;
        break;

    case 'data-social':

        if(!filter_var($postData['user_facebook'], FILTER_VALIDATE_URL)){
            $json['error'] = true;
            $json['errorMessage'] = 'O link informado do facebook, não é válido!';
            break;
        }

        $userCreate = [
            'user_name' => $_SESSION['wizard']['user_name'],
            'user_lastname' => $_SESSION['wizard']['user_lastname'],
            'user_document' => $_SESSION['wizard']['user_document'],
            'user_email' => $_SESSION['wizard']['user_email'],
            'user_password' => $_SESSION['wizard']['user_password'],
            'user_facebook' => $postData['user_facebook'],
            'user_website' => $postData['user_website']
        ];

        $create = new \CRUD\Create;
        $create->create('users', $userCreate);

        $json['redirect'] = 'https://localhost/form_wizard/main.php';
        $json['finish'] = true;
        break;

    default:
        $json['error'] = true;
        $json['errorMessage'] = 'Ação não parametrizada!';
        break;
}

echo json_encode($json);

ob_end_flush();