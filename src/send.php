<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/all.css">
    <title>Website demo</title>
    <script>
        var page = "gui-mai";
    </script>
</head>
<body>
    <header id="headerContainer">
        <div class="headerContent">
            <h1>WEB DEMO</h1>
        </div>
        <div class="menuContent">
            <nav class="nav-header">
                <ul>
                    <li class="active"><a href="/index.php">Trang chu</a></li>
                    <li><a href="#">Danh muc</a></li>
                    <li><a href="#">Tin tuc</a></li>
                    <li><a href="#">Gioi thieu</a></li>
                    <li><a href="/send.php">Gửi mail</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section id="mainContainer">
        <div class="sidebarContent">
            <article class="subSidebar"> 
                <div class="sub-sidebar-title">
                    <p>Danh muc</p>
                </div>
                <div class="sub-sidebar-content">

                </div>
            </article>
            <article class="subSidebar"> 
                <div class="sub-sidebar-title">
                    <p>Bai viet moi</p>
                </div>
                <div class="sub-sidebar-content">

                </div>
            </article>
        </div>
        <div class="mainContent"> 
            <div class="input-content-send-mail">
                <form action="index.html" method="POST">
                    <div class="form-content">
                        <label for="mail">Email: </label>
                        <input id="mail" name="mail" class="input-form" type="mail" placeholder="Nhập vào mail">
                        <label for="title">Tiêu đề: </label>
                        <input id="title" name="title" class="input-form" type="text" placeholder="Nhập vào tiêu đề">
                        <label for="content">Nội dung: </label>
                        <textarea class="textarea-form" name="content" id="content" cols="30" rows="10" placeholder="Nhập vào nội dung"></textarea>
                    </div>
                    <button type="button" id="button-send" class="button-form">Submit</button>
                </form>
            </div>
        </div>
    </section>
    <footer id="footerContainer">
        <p>Copyright &copy; - By Fodo </p>
    </footer>
</body>
<script src="assets/scripts/all.js"></script>
</html>