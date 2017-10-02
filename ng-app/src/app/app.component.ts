import { Component } from '@angular/core';
import { OnInit } from '@angular/core';

import { ShortService } from './short.service';
import { HotkeyModule } from 'angular2-hotkeys';

@Component({
  selector: 'app-root',
  host: {'(window:keydown)': 'hotkeys($event)'},
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  params: object;
  urlCreated: boolean;
  useCustomUrl: boolean;
  fullUrl: string;
  customUrl: string;
  message: string;

  constructor(private shortService: ShortService) { }

  ngOnInit(): void {
    this.getParams();
  }

  // TODO: redo with binding to input and probably need to use angular2-hotkeys
   hotkeys(event): void {
      // ctrl + v hotkey
      if (event.keyCode == 86 && event.ctrlKey) {
        setTimeout(()=> this.shorten(), 100);
      }
   }

  getParams(): void {
    this.shortService.getParams().then(params => this.params = params);
  }

  shorten(): void {
    this.shortService
        .create({
          fullUrl: this.fullUrl,
          shortUrl: this.useCustomUrl ? this.customUrl : null
        }).then(data => {
          if (data.status === 'success') {
            this.urlCreated = true;
            this.message = data.message || '';
            this.fullUrl = location.origin + '/' + data.short_url; // disaply short url into main input
          } else {
            this.urlCreated = false;
            this.message = data.message || 'Unknown error an occured';
          }
        });
  }

  check(): void {
    this.shortService
        .check({
          shortUrl: this.customUrl
        }).then(data => {
          console.info(data);
        });
  }

  copy(): void {
      console.info('copy shortUrl Into buffer ' + this.customUrl);
  }
}
