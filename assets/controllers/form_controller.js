import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  connect() {
    this.onSubmit.bind(this);
    this.element.addEventListener('submit', this.onSubmit);
  }

  onSubmit() {
    document.getElementById('main-blocker').classList.remove('d-none');
    document.getElementById('header-blocker').classList.remove('d-none');
  }
}
