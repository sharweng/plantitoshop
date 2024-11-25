<?php
    session_start();
    include('../includes/config.php');
    include('../includes/notUserRedirect.php');
    if(isset($_POST['create_send'])){
        $rev_id = $_SESSION['rev_id'];
        $user_id = $_POST['email'];
        $rev_num = $_POST['rev_num'];
        $prod_id = $_POST['prod'];
        $message = $_POST['rev_msg'];

        $badWords = ['putangina', "putang ina", 'gago', 'tanga', 'ulol', 'bobo', 'lintek', 'yawa', 'pokpok', 'tarantado',
                    'inamo', 'pucha', 'putcha', 'puta', 'gagi', 'idiot', 'moron', 'stupid', 'bitch', 'ass',
                    'jerk', 'loser', 'slut', 'whore', 'asshole', 'bastard', 'fuck', 'dick', 'burat', 'bayag',
                    'inutil', 'nigger', 'nigga', 'cunt', 'dumbass', 'fucker', 'shithead', 'douchebag', 'retard', 'faggot',
                    'douche', 'jackass', 'bayot', 'pakshet', 'bwisit', 'leche', 'gaga', 'buang', 'boang', 'putragis', 'kupal',
                    'punyeta', 'shet', 'tangina', 'pakyu', 'shit', 'fucking', 'shitty'];
        $pattern = '/\b(' . implode('|', array_map('preg_quote', $badWords)) . ')\b/i';
        $maskedMessage = preg_replace_callback($pattern, function($matches) {
            return str_repeat('*', strlen($matches[0]));
        }, $message);

        $query = "UPDATE review SET user_id = ?, prod_id = ?, rev_num = ?, rev_msg = ? WHERE rev_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiisi", $user_id, $prod_id, $rev_num, $maskedMessage, $rev_id);
        $result = $stmt->execute();

        if($result){
            header("Location: /plantitoshop/review/");
            exit();
        }
    }

    if(isset($_POST['update_prod'])){
        $rev_id = $_POST['rev_id'];
        $user_id = $_POST['user'];
        $rev_num = $_POST['rev_num'];
        $prod_id = $_POST['prod'];
        $message = $_POST['rev_msg'];

        $badWords = ['putangina', "putang ina", 'gago', 'tanga', 'ulol', 'bobo', 'lintek', 'yawa', 'pokpok', 'tarantado',
                    'inamo', 'pucha', 'putcha', 'puta', 'gagi', 'idiot', 'moron', 'stupid', 'bitch', 'ass',
                    'jerk', 'loser', 'slut', 'whore', 'asshole', 'bastard', 'fuck', 'dick', 'burat', 'bayag',
                    'inutil', 'nigger', 'nigga', 'cunt', 'dumbass', 'fucker', 'shithead', 'douchebag', 'retard', 'faggot',
                    'douche', 'jackass', 'bayot', 'pakshet', 'bwisit', 'leche', 'gaga', 'buang', 'boang', 'putragis', 'kupal',
                    'punyeta', 'shet', 'tangina', 'pakyu', 'shit', 'fucking', 'shitty'];
        $pattern = '/' . implode('|', array_map('preg_quote', $badWords)) . '/i';
        $maskedMessage = preg_replace_callback($pattern, function($matches) {
            return str_repeat('*', strlen($matches[0]));
        }, $message);

        $query = "UPDATE review SET user_id = ?, prod_id = ?, rev_num = ?, rev_msg = ? WHERE rev_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiisi", $user_id, $prod_id, $rev_num, $maskedMessage, $rev_id);
        $result = $stmt->execute();

        if($result){
            header("Location: /plantitoshop/view_product.php");
            exit();
        }
    }
?>