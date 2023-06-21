import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AddressService {
  private apiUrl = 'http://localhost:8000/addresses'; // Reemplaza con la URL de tu API Laravel

  constructor(private http: HttpClient) { }

  getAllAddresses(): Observable<any[]> {
    return this.http.get<any[]>(this.apiUrl);
  }

  createAddress(address: any): Observable<any> {
    return this.http.post<any>(this.apiUrl, address);
  }

  updateAddress(id: number, address: any): Observable<any> {
    const url = `${this.apiUrl}/${id}`;
    return this.http.put<any>(url, address);
  }

  deleteAddress(id: number): Observable<any> {
    const url = `${this.apiUrl}/${id}`;
    return this.http.delete<any>(url);
  }
}
