

// Mobile menu functionality
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileNav = document.querySelector('.mobile-nav');

        mobileMenuToggle.addEventListener('click', () => {
            mobileMenuToggle.classList.toggle('active');
            mobileNav.classList.toggle('active');
        });

        // Close mobile menu when clicking on links
        document.querySelectorAll('.mobile-nav a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenuToggle.classList.remove('active');
                mobileNav.classList.remove('active');
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenuToggle.contains(e.target) && !mobileNav.contains(e.target)) {
                mobileMenuToggle.classList.remove('active');
                mobileNav.classList.remove('active');
            }
        });

        // Enhanced smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                
                // Skip if href is just "#"
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Enhanced header functionality
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            const scrolled = window.pageYOffset;
            
            if (scrolled > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Active menu item highlighting
        function updateActiveMenuItem() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-links a, .mobile-nav a');
            
            let currentSection = '';
            const scrollPos = window.pageYOffset + 100;
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                
                if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                    currentSection = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${currentSection}`) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', updateActiveMenuItem);
        window.addEventListener('load', updateActiveMenuItem);

        // Parallax effect for geometric shapes
        window.addEventListener('scroll', () => {
            const shapes = document.querySelectorAll('.shape');
            const scrolled = window.pageYOffset;
            
            shapes.forEach((shape, index) => {
                const speed = (index + 1) * 0.3;
                shape.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.1}deg)`;
            });
        });


        // Form submission effect
        document.querySelector('.submit-btn').addEventListener('click', function(e) {
            e.preventDefault();
            this.innerHTML = 'TRANSMITTING...';
            this.style.background = 'linear-gradient(45deg, #8000ff, #00ffff)';
            
            setTimeout(() => {
                this.innerHTML = 'TRANSMISSION COMPLETE';
                this.style.background = 'linear-gradient(45deg, #00ff00, #00ffff)';
                
                setTimeout(() => {
                    this.innerHTML = 'TRANSMIT TO MATRIX';
                    this.style.background = '#7453fc';
                }, 2000);
            }, 1500);
        });

        

        let radios=document.getElementsByName("radio-type"); //radio
        const transfertDiv = document.getElementById('transfertInput');
        const achatDiv = document.getElementById('achatInput');
        
        radios.forEach(radio => {
         radio.addEventListener("change", () => {
        if (radio.value === "transfert" && radio.checked) {
            transfertDiv.classList.remove("hidden");
            achatDiv.classList.add("hidden");
        } else if (radio.value === "achat" && radio.checked){
            achatDiv.classList.remove("hidden");
            transfertDiv.classList.add("hidden");
        }else{
            transfertDiv.classList.add("hidden");
            achatDiv.classList.add("hidden");
        }
        });
        });