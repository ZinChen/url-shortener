import { Component } from '@angular/core';

@Component({
  selector: 'page-not-found',
  template: `<h2 class="not-found">{{text}}</h2>`,
  styles: ['.not-found{ font-size: 2em; }']
})
export class PageNotFoundComponent {
  text = 'Page Not Found.';
}
