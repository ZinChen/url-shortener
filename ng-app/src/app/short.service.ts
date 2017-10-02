import { Injectable } from '@angular/core';
import { Headers, Http } from '@angular/http';

import 'rxjs/add/operator/toPromise';

@Injectable()
export class ShortService {
  private headers = new Headers({'Content-Type': 'application/json'});

  constructor(private http: Http) { }

  getParams(): Promise<any> {
    return this.http.get('/api/params')
               .toPromise()
               .then(res => res.json())
               .catch(this.handleError);
  }

  create(urlData: any): Promise<any> {
    return this.http.post('/api/create', JSON.stringify(urlData), {headers: this.headers})
               .toPromise()
               .then(res => res.json())
               .catch(this.handleError);
  }

  check(urlData: any): Promise<any> {
    return this.http.post('/api/check', JSON.stringify(urlData), {headers: this.headers})
               .toPromise()
               .then(res => res.json())
               .catch(this.handleError);
  }

  private handleError(error: any): Promise<any> {
    console.error('An error occured', error);
    return Promise.reject(error.message || error);
  }
}