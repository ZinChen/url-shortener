import 'rxjs/add/operator/switchMap';
import { Component } from '@angular/core';
import { OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute, ParamMap, Router } from '@angular/router';

import { ShortService } from './short.service';

@Component({
  selector: 'redirect',
  template: `You will be redirected soon...`,
  styles: []
})
export class RedirectComponent implements OnInit {

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
          this.shortService.used(data.shortUrl).then(usedData => {
            window.location.href = data.fullUrl;
          });
        } else {
          this.router.navigate(['/']);
        }
      });
  }
}
