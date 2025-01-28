<?php
$placeId = $_GET['p'];
// Fetch comments for the specific place from the Review table
$query = "SELECT r.content, r.rating, u.email
          FROM reviews r
		  JOIN users u ON r.userId = u.id
          WHERE r.placeId = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("i", $placeId); // Bind the placeId parameter to the query
$stmt->execute();
$result = $stmt->get_result();
$comments = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments for Place</title>
    <link rel="stylesheet" href="com.css">
</head>
<body>
    <div class="comments-section">
        <h2>Vistors' Experiences</h2>
		
		<div class="comments-list">
        <?php if (count($comments) > 0): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-box">
                    <div class="user-info">
                        <strong><?php echo htmlspecialchars($comment['email']); ?></strong>
                    </div>
                    <div class="comment-content">
                        <p><?php echo htmlspecialchars($comment['content']); ?></p>
                    </div>
                    <div class="comment-rating">
                        <span>Rating: <?php echo $comment['rating']; ?>/5</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

    </div>

</body>
</html>