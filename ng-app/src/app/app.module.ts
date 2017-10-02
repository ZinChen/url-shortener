import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { FormsModule }   from '@angular/forms';
import { RouterModule }   from '@angular/router';

import { AppComponent } from './app.component';
import { ShortenerComponent } from './shortener.component';
import { RedirectComponent } from './redirect.component';
import { PageNotFoundComponent } from './page-not-found.component';
import { InfoComponent } from './info.component';
import { ShortService } from './short.service';

import { ClipboardModule } from 'ngx-clipboard';

@NgModule({
  declarations: [
    AppComponent,
    ShortenerComponent,
    InfoComponent,
    RedirectComponent,
    PageNotFoundComponent
  ],
  imports: [
    BrowserModule,
    HttpModule,
    FormsModule,
    ClipboardModule,
    RouterModule.forRoot([
      { path: '', component: ShortenerComponent }
      , { path: ':shortUrl', component: RedirectComponent}
      , { path: ':shortUrl/info', component: InfoComponent }
      , { path: '**', component: PageNotFoundComponent }
    ], { useHash: true })
  ],
  providers: [ ShortService ],
  bootstrap: [ AppComponent ]
})
export class AppModule { }
