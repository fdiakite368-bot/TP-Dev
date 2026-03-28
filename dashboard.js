const menuToggle = document.getElementById("menuToggle");
const mainNav = document.getElementById("mainNav");
const navLinks = mainNav ? Array.from(mainNav.querySelectorAll("a[href^='#']")) : [];

if (menuToggle && mainNav) {
    menuToggle.addEventListener("click", () => {
        mainNav.classList.toggle("open");
    });
}

const sections = navLinks
    .map((link) => {
        const target = document.querySelector(link.getAttribute("href"));
        return target ? { link, target } : null;
    })
    .filter(Boolean);

const setActiveLink = () => {
    const scrollPosition = window.scrollY + 120;
    let active = null;

    sections.forEach((item) => {
        if (item.target.offsetTop <= scrollPosition) {
            active = item.link;
        }
    });

    navLinks.forEach((link) => link.classList.remove("active"));
    if (active) {
        active.classList.add("active");
    }
};

document.addEventListener("scroll", setActiveLink);
window.addEventListener("load", setActiveLink);
