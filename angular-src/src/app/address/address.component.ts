import { Component, OnInit } from '@angular/core';
import { AddressService } from '../address.service';

@Component({
  selector: 'app-address',
  templateUrl: './address.component.html',
  styleUrls: ['./address.component.css']
})
export class AddressComponent implements OnInit {
  addresses: any[] = [];

  constructor(private addressService: AddressService) { }

  ngOnInit(): void {
    this.getAllAddresses();
  }

  getAllAddresses(): void {
    this.addressService.getAllAddresses()
      .subscribe(addresses => this.addresses = addresses);
  }

  createAddress(address: any): void {
    this.addressService.createAddress(address)
      .subscribe(newAddress => {
        // Manejar la respuesta de la creación de dirección si es necesario
        console.log(newAddress);
        console.log('Enviando dirección:', address);
        this.getAllAddresses();
      });
  }

  updateAddress(id: number, address: any): void {
    this.addressService.updateAddress(id, address)
      .subscribe(updatedAddress => {
        // Manejar la respuesta de la actualización de dirección si es necesario
        console.log(updatedAddress);
        this.getAllAddresses();
      });
  }

  deleteAddress(id: number): void {
    this.addressService.deleteAddress(id)
      .subscribe(() => {
        // Manejar la respuesta de la eliminación de dirección si es necesario
        console.log(`Dirección con ID ${id} eliminada`);
        this.getAllAddresses();
      });
  }
}
