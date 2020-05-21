# ProiectTW

Simple editable task-list that also keeps track of your finished tasks.

### Criterii de acceptanta: 

Aplicatia sa fie [Single Page Application](https://en.wikipedia.org/wiki/Single-page_application) ✔️
Codul sursa (nearhivat) al proiectului trebuie sa fie salvat pe [GitHub](https://github.com/) ✔️
nu puteti folosi librarii, framework-uri [CSS](https://en.wikipedia.org/wiki/CSS_framework) sau [JavaScript](https://en.wikipedia.org/wiki/JavaScript_framework) (cum ar fi jQuery, Bootstrap, Angular, React, etc) pentru realizarea frontend-ului ✔️

#### Frontend (maxim 17 puncte)

##### HTML si CSS (maxim 8 puncte)
Fisiere separate pentru HTML si CSS (0.5 puncte) 
```
/public/main.html
/public/style.css
```

In interiorul documentelor HTML, sa se foloseasca minim 4 [taguri semantice](https://www.w3schools.com/html/html5_semantic_elements.asp) (1 punct) 
```html
main.html

<header>...</header>
<title>...</title>
<article>...</article>
<footer>...</footer>
```

Stilurile CSS sa fie definite folosind clase direct pe elementele care trebuie stilizate (minim 80% din selectori) (0.5 pct)✔️

Layout-ul sa fie impartit in minim 2 coloane si sa fie realizat cu [Flexbox](https://css-tricks.com/snippets/css/a-guide-to-flexbox/) si/sau [CSS grid](https://css-tricks.com/snippets/css/complete-guide-grid/) (2 puncte)
```css
styles.css
.page-grid {
    margin: 0;
    display: grid;
    grid-template-columns: 1fr 550px 550px 1fr;
    grid-template-areas:
        ". container_t container_f ."
        ". footer footer .";
}
```

Site-ul sa fie [responsive](https://www.w3schools.com/html/html_responsive.asp), respectand rezolutiile urmatoarelor dispozitive folosind [media queries](https://www.uxpin.com/studio/blog/media-queries-responsive-web-design/): (4 puncte)
```css

  @media (max-width: 1280px) and (min-width: 768px) 
  @media (max-width: 768px) 

```

##### Javascript (maxim 9 puncte)
Fisier separat JavaScript (0.5 puncte) 
```
/public/script.js
```
Manipularea DOM-ului (crearea, editarea si stergerea elementelor/nodurilor HTML) (3 puncte) 
```js
script.js 
let el = document.createElement('div');
finishedTasksContainer.appendChild(el);
element.classList.add("editable");
element.classList.remove("editable");
```
Folosirea evenimentelor JavaScript declansate de mouse/tastatura (1 punct) 
```html
 <button class="task_button task_done_button task_display_button" onclick = "task_done('{{id}}');">
<button class="task_button task_edit_button task_display_button" onclick = "task_edit('{{id}}');">
```

 Utilizarea [AJAX](https://www.w3schools.com/xml/ajax_intro.asp) ([GET, POST, PUT, DELETE](http://www.restapitutorial.com/lessons/httpmethods.html)) (4 puncte) ✔️

Folosirea localStorage (0.5 puncte)
```js
script.js
 user = localStorage.getItem('user');
localStorage.setItem('user', JSON.stringify(API.user));
```

#### Backend API (maxim 8 puncte)

Creare server Backend (2 puncte)
```
src/api
```
CRUD API (Create, Read, Update si Delete) pentru a servi Frontend-ului (6 puncte)
```
src/api/Action/Task/Create.php
src/api/Action/Task/Read.php
src/api/Action/Task/Update.php
src/api/Action/Task/Delete.php
```
