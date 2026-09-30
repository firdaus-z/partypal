<?php
session_start();
if (isset($_GET['catererID'])) {
    $catererID = $_GET['catererID'];
    require_once('connection.php');
    
    try {
        // Start a transaction
        $conn->beginTransaction();
        
        // Delete from caterers table
        $statement = $conn->prepare("DELETE FROM caterers WHERE catererID=:catererID");
        $statement->execute(array(':catererID' => $catererID));
        $conn->commit();
        
        $_SESSION['message'] = 'Caterer has been deleted successfully.';
    } catch (Exception $e) {
        // Rollback the transaction if something failed
        $conn->rollBack();
        $_SESSION['message'] = 'Failed to delete caterer: ' . $e->getMessage();
    }
    
    header('Location: caterer.php');
} else {
    header('Location: logout.php');
}
?>
