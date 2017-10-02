import { Component } from '@angular/core';
import { OnInit } from '@angular/core';

import { ShortService } from './short.service';
import { HotkeyModule } from 'angular2-hotkeys';

@Component({
  selector: 'shortener',
  host: {'(window:keydown)': 'hotkeys($event)'},
  templateUrl: './shortener.component.html',
  styleUrls: ['./shortener.component.css']
})
export class ShortenerComponent implements OnInit {
  params: object;
  urlCreated: boolean;
  useCustomUrl: boolean;
  fullUrl: string;
  customUrl: string;
  originalUrl: string;
  resultShortUrl: string;
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
            this.originalUrl = this.fullUrl;
            this.customUrl = data.short_url;
            this.resultShortUrl = location.origin + '/' + data.short_url;
            this.fullUrl = this.resultShortUrl; // display short url into main inputs
          } else {
            this.urlCreated = false;
            this.message = data.message || 'Unknown error an occured';
          }
        });
  }

  info(): void {
    this.shortService
        .info(this.customUrl).then(data => {
          console.info(data);
        });
  }
}
