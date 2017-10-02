import { Component } from '@angular/core';
import { OnInit } from '@angular/core';

import { ShortService } from './short.service';

@Component({
  selector: 'app-root',
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
