/* /// Подсветка активного пункта меню при скролле
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');

    function changeActiveLink() {
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionBottom = sectionTop + section.offsetHeight;
            
            if (window.scrollY >= sectionTop && window.scrollY < sectionBottom) {
                currentSection = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');
            // Защита от null href
            if (href && href.startsWith('#')) {
                const id = href.substring(1);
                if (id === currentSection) {
                    link.classList.add('active');
                }
            }
        });
    }
    
    window.addEventListener('scroll', changeActiveLink);
    changeActiveLink();
}); */

// Обработка статусов карточек портфолио
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.portfolio-card');
    
    // Если карточек нет - просто выходим
    if (cards.length === 0) return;
    
    cards.forEach(card => {
        const status = card.getAttribute('data-status') || 'in-progress';
        const links = card.querySelectorAll('a');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                if (status === 'completed') {
                    const href = this.getAttribute('href');
                    if (!href || href === '#' || href === '') {
                        e.preventDefault();
                        alert('✅ Проект готов, но ссылка пока не добавлена!');
                    }
                    // Если href есть и не # - переход разрешён
                } else if (status === 'in-progress') {
                    e.preventDefault();
                    alert('🚧 Этот проект ещё в разработке. Скоро появится!');
                } else if (status === 'planning') {
                    e.preventDefault();
                    alert('📋 Проект в планах. Следите за обновлениями!');
                } else if (status === 'review') {
                    e.preventDefault();
                    alert('🔍 Проект на проверке. Скоро будет доступен!');
                } else {
                    // На случай неизвестного статуса
                    e.preventDefault();
                    alert('ℹ️ Статус проекта неизвестен.');
                }
            });
        });
    });
});

// Обработка статусов для list-item (список проектов)
document.addEventListener('DOMContentLoaded', function() {
    const listItems = document.querySelectorAll('.list-item');
    
    if (listItems.length === 0) return;
    
    listItems.forEach(item => {
        const status = item.getAttribute('data-status') || 'completed';
        const links = item.querySelectorAll('a');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                if (status === 'completed') {
                    const href = this.getAttribute('href');
                    if (!href || href === '#' || href === '') {
                        e.preventDefault();
                        alert('✅ Проект готов, но ссылка пока не добавлена!');
                    }
                    // Если href есть и не # - переход разрешён
                } else if (status === 'in-progress') {
                    e.preventDefault();
                    alert('🚧 Этот проект ещё в разработке. Скоро появится!');
                } else if (status === 'planning') {
                    e.preventDefault();
                    alert('📋 Проект в планах. Следите за обновлениями!');
                } else if (status === 'review') {
                    e.preventDefault();
                    alert('🔍 Проект на проверке. Скоро будет доступен!');
                } else {
                    e.preventDefault();
                    alert('ℹ️ Статус проекта неизвестен.');
                }
            });
        });
    });
});