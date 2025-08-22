<?php

// === CẤU HÌNH THỜI GIAN TỒN TẠI CỦA SESSION ===
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);
session_start();

// BƯỚC 1: THÊM ĐOẠN MÃ NÀY ĐỂ XỬ LÝ YÊU CẦU XÓA SESSION
// Kiểm tra xem URL có tham số ?clear_session=true hay không, nếu có, xóa dữ liệu form đã lưu trong session
if (isset($_GET['clear_session']) && $_GET['clear_session'] === 'true') {
    unset($_SESSION['form_data']);
    header('Location: index.php');
    exit();
}

$errors = [];
$save_message = '';

// Lấy dữ liệu từ session để điền lại vào form (nếu có).
$first_name = $_SESSION['form_data']['first_name'] ?? '';
$last_name  = $_SESSION['form_data']['last_name'] ?? '';
$email      = $_SESSION['form_data']['email'] ?? '';
$invoice_id = $_SESSION['form_data']['invoice_id'] ?? '';
$categories = $_SESSION['form_data']['category'] ?? [];
$message    = $_SESSION['form_data']['message-box'] ?? '';

$uploaded_file_path = '';
$form_submitted_successfully = false;

// Kiểm tra nếu form được submit bằng phương thức POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // BƯỚC A: Lấy và làm sạch dữ liệu từ form (luôn thực hiện)
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $invoice_id = trim($_POST['invoice_id'] ?? '');
    $categories = $_POST['category'] ?? [];
    $message    = trim($_POST['message-box'] ?? '');

    // BƯỚC B: Lưu dữ liệu vừa nhập vào session (luôn thực hiện)
    $_SESSION['form_data'] = [
        'first_name'  => $first_name,
        'last_name'   => $last_name,
        'email'       => $email,
        'invoice_id'  => $invoice_id,
        'category'    => $categories,
        'message-box' => $message,
    ];

    // BƯỚC C: Kiểm tra xem nút nào đã được nhấn
    $action = $_POST['action'] ?? 'submit'; // Mặc định là 'submit'

    // TRƯỜNG HỢP 1: Nhấn nút "Save Progress"
    if ($action === 'save') {
        $save_message = "Your progress has been saved successfully!";
    }

    // TRƯỜNG HỢP 2: Nhấn nút "Submit Payment"
    elseif ($action === 'submit') {
        if (empty($first_name)) $errors['first_name'] = 'First Name is required.';
        if (empty($last_name)) $errors['last_name'] = 'Last Name is required.';
        if (empty($email)) $errors['email'] = 'Email is required.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email format.';
        if (empty($invoice_id)) $errors['invoice_id'] = 'Invoice ID is required.';
        if (empty($categories)) $errors['categories'] = 'Please select at least one category.';

        if (isset($_FILES['file-upload']) && $_FILES['file-upload']['error'] == 0) {
            $file = $_FILES['file-upload'];
            $file_name = $file['name'];
            $file_tmp_name = $file['tmp_name'];
            $file_size = $file['size'];
            
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_exts)) {
                if ($file_size <= 1048576*3) { // 1MB
                    $uploaded_file_path = $file_name; 
                    // $new_file_name = uniqid('', true) . '.' . $file_ext;
                    // $upload_destination = dirname(dirname(__DIR__)) . '/uploads/' . $new_file_name;
                    
                    // if (move_uploaded_file($file_tmp_name, $upload_destination)) {
                    //     $uploaded_file_path = $upload_destination;
                    // } else {
                    //     $errors['file'] = 'Failed to move uploaded file.';
                    // }
                } else {
                    $errors['file'] = 'File is too large! Maximum size is 1MB.';
                }
            } else {
                $errors['file'] = 'Invalid file type. Only jpg, jpeg, png, gif are allowed.';
            }
        } else {
            $errors['file'] = 'Payment receipt is required.';
        }

        if (empty($errors)) {
            $form_submitted_successfully = true;
            unset($_SESSION['form_data']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../font-6-pro/css/all.css">
    <link rel="stylesheet" href="style.css">
    <title>Valid Form</title>
</head>
<body>

    <?php if ($form_submitted_successfully): ?>
        
        <div class="success-container">
            <h2><i class="fa-solid fa-check-circle"></i> Payment Submitted Successfully!</h2>
            <p>Thank you for your submission. Here is the information you provided:</p>
            <ul>
                <li><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></li>
                <li><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
                <li><strong>Invoice ID:</strong> <?php echo htmlspecialchars($invoice_id); ?></li>
                <li><strong>Paid For:</strong>
                    <ul>
                        <?php foreach ($categories as $category): ?>
                            <li><?php echo htmlspecialchars($category); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php if (!empty($message)): ?>
                    <li><strong>Additional Information:</strong> <?php echo nl2br(htmlspecialchars($message)); ?></li>
                <?php endif; ?>
            </ul>
            <h4>Uploaded Receipt:</h4>
            <img src="<?php echo htmlspecialchars($uploaded_file_path); ?>" alt="Uploaded Receipt">
            <div class="back-button-wrapper">
                <a href="index.php" class="back-btn">Trở lại biểu mẫu</a>
            </div>
        </div>

    <?php else: ?>

        <h2>Payment Receipt Upload Form</h2>
        <?php if (!empty($save_message)): ?>
            <div class="save-success-message"><?php echo $save_message; ?></div>
        <?php endif; ?>
        <form class="form" method="POST" action="index.php" enctype="multipart/form-data">
            <div class="form-input">
                <div class="info">
                    <label class="label-top" for="first_name">Name *</label>
                    <input type="text" id="first_name" name="first_name" class="inf" value="<?php echo htmlspecialchars($first_name); ?>">
                    <label class="label-bot" for="first_name">First Name</label>
                    <?php if (isset($errors['first_name'])): ?><div class="error-message"><?php echo $errors['first_name']; ?></div><?php endif; ?>
                </div>
                <div class="info">
                    <label class="label-top" for="last_name"></label>
                     <input type="text" id="last_name" name="last_name" class="inf" value="<?php echo htmlspecialchars($last_name); ?>">
                    <label class="label-bot" for="last_name">Last Name</label>
                    <?php if (isset($errors['last_name'])): ?><div class="error-message"><?php echo $errors['last_name']; ?></div><?php endif; ?>
                </div>
                <div class="info">
                    <label class="label-top" for="email">Email *</label>
                     <input type="text" id="email" name="email" class="inf" value="<?php echo htmlspecialchars($email); ?>">
                    <label class="label-bot" for="email">example@example.com</label>
                    <?php if (isset($errors['email'])): ?><div class="error-message"><?php echo $errors['email']; ?></div><?php endif; ?>
                </div>
                <div class="info">
                    <label class="label-top" for="invoice_id">Invoice ID *</label>
                     <input type="text" id="invoice_id" name="invoice_id" class="inf" value="<?php echo htmlspecialchars($invoice_id); ?>">
                    <label class="label-bot" for="invoice_id"></label>
                    <?php if (isset($errors['invoice_id'])): ?><div class="error-message"><?php echo $errors['invoice_id']; ?></div><?php endif; ?>
                </div>
            </div>
            
            <div class="checkboxes">
                <label class="label-top">Pay For *</label>
                <div class="boxes">
                    <?php
                        $all_categories = [
                            "15K Category", "35K Category", "55K Category", "75K Category",
                            "116K Category", "Shuttle One Way", "Shuttle Two Ways",
                            "Training Cap Merchandise", "Compressport T-Shirt Merchandise",
                            "Buf Merchandise", "Other"
                        ];
                        foreach ($all_categories as $cat) {
                            $checked = in_array($cat, $categories) ? 'checked' : '';
                            echo "<label><input type='checkbox' name='category[]' value='$cat' $checked> $cat</label>";
                        }
                    ?>
                </div>
                <?php if (isset($errors['categories'])): ?><div class="error-message"><?php echo $errors['categories']; ?></div><?php endif; ?>
            </div>
            
            <div class="upload">
                <label for="file-upload" class="upload-box" id="drop-area">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p id="browse-file">Browse Files</p>
                    <p id="file-name-display">Drag and drop files here</p>
                    <input id="file-upload" name="file-upload" type="file" accept=".jpg,.jpeg,.png,.gif" />
                </label>
            </div>
            <?php if (isset($errors['file'])): ?><div class="error-message"><?php echo $errors['file']; ?></div><?php endif; ?>
            <small>jpg, jpeg, png, gif (3mb max.)</small>
                
            <div class="message">
                <label for="message-box" class="label-top">Additional Information (optional)</label>
                <textarea name="message-box" id="message-box" class="message-box" rows="5" cols="40" placeholder="Type here..."><?php echo htmlspecialchars($message); ?></textarea>
            </div>

            <div class="submit-button-container">
                <a href="index.php?clear_session=true" class="clear-btn">xóa biểu mẫu</a>

                <button type="submit" name="action" value="save" class="save-btn">Lưu quá trình</button>
                <button type="submit" name="action" value="submit" class="submit-btn">Nộp biểu mẫu</button>
            </div>
        </form>
    <?php endif; ?>
    <!-- <script>
        const serverData = {
            hasSession: "?php echo isset($_SESSION['form_data']) && !empty($_SESSION['form_data']) ? 'true' : 'false'; ?>"
        };
    </script> -->
    <script src="script.js"></script>
    </body>
</html>
</body>
</html>