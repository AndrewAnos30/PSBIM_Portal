<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'navbar.php'; ?>
        <div class="content-container">
            <div class="card-container">
                <div class="profile-header">
                    <ul class="profile-tabs">
                        <li class="active">Profile</li>
                    </ul>
                </div>
                <div class="lower-container">
                    <div class="left-lower-container">
                        <div class="profile-image">
                            <img src="img/male.jpg" alt="Male">
                        </div>
                        <div class="exam-info">
                            <p><strong>Examination ID:</strong> 20251001</p>
                            <p><strong>Status:</strong> Pending Examination</p>
                        </div>
                    </div>
                    <div class="right-lower-container">
                        <div class="profile-item" id="username">
                            <span><strong>Username:</strong> JBC20251001</span>
                        </div>
                        <div class="profile-item" id="password">
                            <span><strong>Password:</strong> **********</span>
                        </div>
                        <div class="profile-item" id="FirstName">
                            <span><strong>First Name:</strong> John Benedict</span>
                        </div>
                        <div class="profile-item" id="MiddleName">
                            <span><strong>Middle Name:</strong> </span>
                        </div>
                        <div class="profile-item" id="LastName">
                            <span><strong>Last Name:</strong> Cueto</span>
                        </div>
                        <div class="profile-item" id="ExtensionName">
                            <span><strong>Extension Name:</strong> JR.</span>
                        </div>
                        <div class="profile-item" id="Gender">
                            <span><strong>Gender:</strong> Male?</span>
                        </div>
                        <div class="profile-item" id="DOB">
                            <span><strong>Date of Birth:</strong> January 01, 0000</span>
                        </div>
                        <div class="profile-item" id="Email">
                            <span><strong>Email:</strong> johnbenedict.cueto@pcp.org.ph</span>
                        </div>
                        <div class="profile-item" id="Mobile">
                            <span><strong>Mobile:</strong> +639075534498</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>