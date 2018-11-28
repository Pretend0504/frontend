class Slider{
    constructor(id){
      this.container = document.getElementById(id);
      this.items = this.container.querySelectorAll('.slider-list__item, .slider-list__item--selected');
    }
    getSelectedItem(){
      let selected = this.container.querySelector('.slider-list__item--selected');
      return selected
    }
    getSelectedItemIndex(){
      return Array.from(this.items).indexOf(this.getSelectedItem());
    }
    slideTo(idx){
      let selected = this.getSelectedItem();
      if(selected){ 
        selected.className = 'slider-list__item';
      }
      let item = this.items[idx];
      if(item){
        item.className = 'slider-list__item--selected';
      }
    }
    slideNext(){
      let currentIdx = this.getSelectedItemIndex();
      let nextIdx = (currentIdx + 1) % this.items.length;
      this.slideTo(nextIdx);
    }
    slidePrevious(){
      let currentIdx = this.getSelectedItemIndex();
      let previousIdx = (this.items.length + currentIdx - 1) % this.items.length;
      this.slideTo(previousIdx);  
    }
  }
  
  let slider = new Slider('my-slider');
  setInterval(slider.slideNext.bind(slider), 3000);