document.addEventListener("DOMContentLoaded", function(){
    console.error("There is no scripts loaded!");
    console.warn("Loading scripts... Please wait...");
    console.log("Done with preloader.js");
    font = document.createElement('style');
    font.innerHTML = '@import url("https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100;200;300;400;500;600;700;800;900&display=swap");';
    document.body.append(font);
    ui = document.createElement('script');
    ui.setAttribute('src','/assets/js/ui.js');
    document.body.append(ui);
    main = document.createElement('script');
    main.setAttribute('src','/assets/js/main.js');
    document.body.append(main);
})