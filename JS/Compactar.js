
const userData = {
    username: "<?php echo addslashes($User['username'] ?? ''); ?>",
    email: "<?php echo addslashes($User['email'] ?? ''); ?>",
    imagem: "<?php echo addslashes($User['imagem'] ?? 'default.jpg'); ?>"
};

localStorage.setItem("userData", JSON.stringify(userData));

