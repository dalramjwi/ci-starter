<?php if ($this->session->flashdata('message')): ?>
    <div id="toast" class="toast">
        <?= htmlspecialchars($this->session->flashdata('message')) ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast');
            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 2000);
        });
    </script>

    <style>
        .toast {
            position: fixed;
            bottom: 10%;
            left: 50%;
            transform: translateX(-50%) translateY(20px) scale(0.7);
            background-color: #333;
            color: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            opacity: 0;
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 1000;
            font-size: 1.1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0) scale(1.15);
        }
    </style>
<?php endif; ?>
