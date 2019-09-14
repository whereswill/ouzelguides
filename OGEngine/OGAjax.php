<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/ASEngine/AS.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/OGEngine/OG.php';

//csrf protection
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') 
    die("Sorry bro!");

$url = parse_url( isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
if( !isset( $url['host']) || ($url['host'] != $_SERVER['SERVER_NAME']))
    die("Sorry bro!");

$action = $_POST['action'];

switch ($action) {

    case "updateProfile":
        onlyAdmin();
        
        $user = new User();
        $user->set_user_id($_POST['user_id']);
        $user->updateProfile($_POST['userDetails']);
        break;

    case "updateUser":
        $user = new User();
        $user->set_user_id($_POST['user_id']);
        $user->updateUser($_POST['userDetails']);
        break;

    case "addPhone":
        $user = new User();
        $user->set_user_id($_POST['user_id_fk']);
        echo $user->addPhone($_POST['phoneDetails']);
        break;

    case "deletePhone":
        $user = new User();
        echo $user->deletePhone($_POST['phone_id']);
        break;

    case "addAddress":
        $user = new User();
        $user->set_user_id($_POST['user_id']);
        echo $user->addAddress($_POST['addressDetails']);
        break;

    case "deleteAddress":
        $user = new User();
        echo $user->deleteAddress($_POST['address_id']);
        break;

    case "addEC":
        $user = new User();
        $user->set_user_id($_POST['user_id']);
        echo $user->addEC($_POST['ecDetails']);
        break;

    case "deleteEC":
        $user = new User();
        echo $user->deleteEC($_POST['ec_id']);
        break;

    case "updateMed":
        $user = new User();
        $user->set_user_id($_POST['user_id']);
        $user->updateMeds($_POST['userMed']);
        break;

    case "changePassword":
        onlyAdmin();

        $user = new User();
        $user->set_user_id($_POST['user_id']);
        $user->changePassword($_POST['userPass']);
        break;

    case "addGuide":
        onlyAdmin();

        $guide = new Guide();
        $guide->addGuide($_POST['guideDetails']);
        break;

    case "updateGuide":
        onlyAdmin();

        $guide = new Guide();
        $guide->set_guide_id($_POST['user_id']);
        $guide->updateGuide($_POST['guideDetails']);
        break;

    case "addPayRate":
        onlyAdmin();

        $guide = new Guide();
        $guide->set_guide_id($_POST['user_id']);
        echo $guide->addPayRate($_POST['payrate_id'], $_POST['notes'], $_POST['visitor_id']);
        break;

    case "deletePayRate":
        onlyAdmin();

        $guide = new Guide();
        $guide->deletePayRate($_POST['guidepayrate_id']);
        break;

    case "addCert":
        onlyAdmin();

        $guide = new Guide();
        $guide->set_guide_id($_POST['user_id']);
        echo $guide->addCert($_POST['cert_id'], $_POST['exp_date']);
        break;

    case "deleteCert":
        onlyAdmin();

        $guide = new Guide();
        $guide->deleteCert($_POST['guidecert']);
        break;

    case "addGuideSkill":
        onlyAdmin();

        $guide = new Guide();
        $guide->set_guide_id($_POST['user_id']);
        echo $guide->addGuideSkill($_POST['skill_id'], $_POST['notes']);
        break;

    case "deleteGuideSkill":
        onlyAdmin();

        $guide = new Guide();
        $guide->deleteGuideSkill($_POST['guideskill_id']);
        break;

    case "addRiver":
        onlyAdmin();

        $guide = new Guide();
        $guide->set_guide_id($_POST['user_id']);
        echo $guide->addGuideRiver($_POST['rivertrip_id'], $_POST['notes']);
        break;

    case "deleteRiver":
        onlyAdmin();

        $guide = new Guide();
        $guide->deleteGuideRiver($_POST['guideriver_id']);
        break;

    case "addUserNote":
        onlyAdmin();

        $note = new Note();
        echo $note->insertUserNote($_POST['user_id'], $_POST['visitor_id'], $_POST['note'], $_POST['public_check']);
        break;

    case "deleteNote":
        onlyAdmin();

        $note = new Note();
        $note->deleteUserNote($_POST['note_id']);
        break;

    case "banUser":
        onlyAdmin();

        $user = new User();
        $user->set_user_id($_POST['userId']);
        $user->updateInfo(array( 'banned' => 'Y' ));
        break;

    case "unbanUser":
        onlyAdmin();

        $user = new User();
        $user->set_user_id($_POST['userId']);
        $user->updateInfo(array( 'banned' => 'N' ));
        break;

    case "deactivateUser":
        onlyAdmin();

        $user = new User();
        $user->set_user_id($_POST['userId']);
        $user->updateDetails(array( 'active' => 'N' ));
        break;

    case "activateUser":
        onlyAdmin();

        $user = new User();
        $user->set_user_id($_POST['userId']);
        $user->updateDetails(array( 'active' => 'Y' ));
        break;
        
    case "changeRole":
        onlyAdmin();

        $user = new User();
        $user->set_user_id($_POST['userId']);
        echo ucfirst($user->changeRole());
        break;

    case "deactivateGuide":
        onlyAdmin();

        $guide = new Guide();
        $guide->set_guide_id($_POST['userId']);
        $guide->updateGuideDetails(array( 'active_bool' => 'N' ));
        break;

    case "activateGuide":
        onlyAdmin();

        $guide = new Guide();
        $guide->set_guide_id($_POST['userId']);
        $guide->updateGuideDetails(array( 'active_bool' => 'Y' ));
        break;

    case "addSkill":
        onlyAdmin();

        $skill = new Skill();
        echo $skill->addSkill($_POST['skillDetails']);
        break;

    case "updateSkill":
        onlyAdmin();

        $skill = new Skill();
        echo $skill->updateSkill($_POST['skillDetails']);
        break;

    case "getSkill":
        onlyAdmin();

        $skill = new Skill();
        echo json_encode($skill->getSkill($_POST['id']));
        break;

    case "deleteSkill":
        onlyAdmin();

        $skill = new Skill();
        $skill->deleteSkill($_POST['skill_id']);
        break;
	
	default:
		
		break;
}

function onlyAdmin() {
    $login = new ASLogin();
    if ( ! $login->isLoggedIn() ) exit();

    $loggedUser = new ASUser(ASSession::get("user_id"));
    if( ! $loggedUser->isAdmin() ) exit();
}