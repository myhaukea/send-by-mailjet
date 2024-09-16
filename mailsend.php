<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mailjet API URL
    $url = 'https://api.mailjet.com/v3.1/send';
    // API参考https://dev.mailjet.com/email/guides/
    
    // API Key 和 Secret Key 在 https://app.mailjet.com/account/apikeys 中生成
    $apiKey = 'abcd1234';
    $apiSecret = 'password';

    // 从表单中获取数据
    $toEmail = isset($_POST['toEmail']) ? $_POST['toEmail'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $htmlPart = isset($_POST['htmlPart']) ? $_POST['htmlPart'] : '';
    $textPart = isset($_POST['textPart']) ? $_POST['textPart'] : ''; 

    // 验证输入
    if (empty($toEmail) || empty($subject) || empty($htmlPart) || empty($textPart)) {
        echo "All fields are required.";
        exit;
    }

    // 处理附件
    $attachments = [];
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['attachment']['tmp_name'];
        $fileName = $_FILES['attachment']['name'];
        $fileSize = $_FILES['attachment']['size'];
        $fileType = $_FILES['attachment']['type'];
        
        // 读取文件内容并编码为 Base64
        $fileContent = file_get_contents($fileTmpPath);
        $base64Content = base64_encode($fileContent);

        $attachments[] = array(
            "ContentType" => $fileType,
            "Filename" => $fileName,
            "Base64Content" => $base64Content
        );
    }

    // 创建 cURL 句柄
    $ch = curl_init($url);

    // 设置 cURL 选项
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");

    // 设置 POST 数据
    $data = array(
        "Messages" => array(
            array(
                "From" => array(
                    "Email" => "send@test.test",//建议先在 https://app.mailjet.com/account/sender 中添加Sender
                    "Name" => "Nodeseek user" // 发件人名称
                ),
                "To" => array(
                    array(
                        "Email" => $toEmail,
                        "Name" => "Recipient"
                    )
                ),
                "Subject" => $subject,  // 主题
                "TextPart" => $textPart, // 摘要
                "HTMLPart" => $htmlPart, // 正文
                "CustomID" => "AppGettingStartedTest",
                "Attachments" => $attachments
            )
        )
    );

    // 将数据编码为 JSON 格式
    $jsonData = json_encode($data);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // 执行 cURL 请求并获取响应
    $response = curl_exec($ch);

    // 检查 cURL 错误
    if (curl_errno($ch)) {
        $error = 'Curl error: ' . curl_error($ch);
    } else {
        $error = null;
    }

    // 关闭 cURL 句柄
    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email with Attachment</title>
</head>
<body>
    <h1>Send Email with Attachment</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="toEmail">收件人:</label>
        <input type="email" id="toEmail" name="toEmail" required><br><br>

        <label for="subject">主题:</label>
        <input type="text" id="subject" name="subject" required><br><br>

        <label for="htmlPart">正文（支持HTML）:</label><br>
        <textarea id="htmlPart" name="htmlPart" rows="4" cols="50" required></textarea><br><br>

        <label for="textPart">摘要:</label><br>
        <textarea id="textPart" name="textPart" rows="4" cols="50" required></textarea><br><br>

        <label for="attachment">附件:</label>
        <input type="file" id="attachment" name="attachment"><br><br>

        <input type="submit" value="Send">
    </form>

    <?php if (isset($response)): ?>
        <h2>Response</h2>
        <pre><?php echo htmlspecialchars($response); ?></pre>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <h2>Error</h2>
        <pre><?php echo htmlspecialchars($error); ?></pre>
    <?php endif; ?>
</body>
</html>
