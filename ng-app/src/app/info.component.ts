import 'rxjs/add/operator/switchMap';
import { Component } from '@angular/core';
import { OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';

import { ShortService } from './short.service';

@Component({
  selector: 'info',
  templateUrl: './info.component.html',
  styleUrls: ['./info.component.css']
})
export class InfoComponent implements OnInit {
  fullUrl: string;
  shortUrl: string;
  useCount: number;
  date: string;

  constructor(
    private shortService: ShortService,
    private route: ActivatedRoute,
    private router: Router,
    private location: Location
  ) { }

  ngOnInit(): void {
    this.route.paramMap
      .switchMap((params: ParamMap) => this.shortService.info(params.get('shortUrl')))
      .subscribe(data => {
        if (data.status === 'success') {
          this.fullUrl = data.fullUrl;
          this.shortUrl = location.origin + '/' + data.shortUrl;
          this.date = data.createDate;
          this.useCount = data.useCount;
        } else {
          this.router.navigate(['/']);
        }
      });
  }
}
