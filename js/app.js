const Page = {
	init: function () {
		this.setLabels();
		Popup.init();
	},

	setLabels: function () {
		let labels = id('.i-label');

		if (labels !== false) {
			if (typeof labels[0] !== 'object')
				labels = [labels];

			labels.forEach(item => {
				item.firstChild.addEventListener('focus', () => item.classList.add('focused'));
				item.firstChild.addEventListener('blur', () => item.classList[item.firstChild.value ? 'add' : 'remove']('focused'));
			});
		}
	},

	dispatch: function ({ action, payload }) {
		switch (action) {
			case 'add':
				Items.add(payload);
				break;

			case 'update':
				Items.update(payload);
				break;

			case 'delete':
				Items.remove(payload);
				break;
		}
	}
};

const Items = {
	add: function (payload) {
	    if (id('#no-items'))
	        id('#no-items').remove();
	    
		const BookItem = document.createElement('div');
		BookItem.id = `book-item-${payload.item_id}`;
		BookItem.className = 'book-item tabled';

		const Fio = document.createElement('div');
		Fio.innerText = payload.fio;
		Fio.className = 'book-item-fio';
		BookItem.appendChild(Fio);

		const Phone = document.createElement('div');
		Phone.innerText = payload.phone;
		Phone.className = 'book-item-phone';
		BookItem.appendChild(Phone);

		const Who = document.createElement('div');
		Who.innerText = payload.who;
		Who.className = 'book-item-who';
		BookItem.appendChild(Who);

		const BookItemButtons = document.createElement('div');
		BookItemButtons.className = 'book-item-buttons';

		const EditButton = document.createElement('div');
		EditButton.className = 'button center';
		EditButton.innerText = '🖉';
		EditButton.addEventListener('click', () => this.edit(payload.item_id));

		const RemoveButton = document.createElement('div');
		RemoveButton.className = 'button center';
		RemoveButton.innerText = '×';
		RemoveButton.addEventListener('click', () => this.delete(payload.item_id));

		BookItemButtons.appendChild(EditButton);
		BookItemButtons.appendChild(RemoveButton);

		BookItem.appendChild(BookItemButtons);

		id('#book-items').appendChild(BookItem);
	},

	remove: function (payload) {
		id(`#book-item-${payload.item_id}`).remove();
		
		if (id('#book-items').children.length === 0)
		    id('#book-items').setHTML("<div id='no-items'>Записей не найдено</div>");
	},

	update: function (payload) {
		const sourceNode = id(`#book-item-${payload.item_id}`);

		id('.book-item-fio', sourceNode).setHTML(payload.fio);
		id('.book-item-phone', sourceNode).setHTML(payload.phone);
		id('.book-item-who', sourceNode).setHTML(payload.who);
	},

	delete: async function (item_id) {
		id(`#book-item-${item_id}`).loading(true);
		await Api('api/pb.php', 'DELETE', { item_id });
	},

	edit: function (item_id) {
		Popup.fill(item_id);
		Popup.show(true);
	}
};

const Popup = {
	init: function () {
		id('#add-button').addEventListener('click', () => this.show(true));
		id('#popup-add-close').addEventListener('click', () => this.show(false));
		id('#popup-add-cancel').addEventListener('click', () => this.show(false));
		id('#popup-add-save').addEventListener('click', () => this.save());
	},
	show: function (show) {
		id('#popup-add').css(show ? 'display: flex;' : '');
		id('#content').css(show ? 'opacity: 0.4;' : '');
		if (!show)
			this.fill();
	},
	fill: function (source) {
		const sourceNode = id(`#book-item-${source}`);

		id('#fio').setValue(source ? id('.book-item-fio', sourceNode).getHTML() : '');
		id('#who').setValue(source ? id('.book-item-who', sourceNode).getHTML() : '');
		id('#phone').setValue(source ? id('.book-item-phone', sourceNode).getHTML() : '');
		id('#item-id').setValue(source ? source : '');

		id('#fio').parentNode.classList[source ? 'add' : 'remove']('focused');
		id('#who').parentNode.classList[source ? 'add' : 'remove']('focused');
		id('#phone').parentNode.classList[source ? 'add' : 'remove']('focused');
	},
	save: async function () {
		const fio = id('#fio').getValue();
		const phone = id('#phone').getValue();
		const who = id('#who').getValue();
		const item_id = id('#item-id').getValue();

		id('.popup-body').loading(true);

		await Api(
			'api/pb.php',
			item_id
				? 'PUT'
				: 'POST'
			,
			{ fio, phone, who, item_id }
		);

		id('.popup-body').loading(false);
		Popup.show(false);
	}
};

const Api = async (path, method, query = false) => {
	const headers = method === 'POST'
		? { 'Content-Type': 'application/json;charset=utf-8' }
		: {}
		;
	let body = query
		? JSON.stringify(query)
		: ''
		;

	const response = await fetch(path, {
		method,
		headers,
		body
	});

	const result = await response.json();

	Page.dispatch(result);
};

document.addEventListener("DOMContentLoaded", () => Page.init());

const id = (selector, _parent = document) => {
	const elem = _parent.querySelectorAll(selector);

	for (let i = 0; i < elem.length; i++) {
		const el = elem[i];

		el.css = (s) => { if (s) el.setAttribute('style', s); else el.removeAttribute('style'); return el; };
		el.remove = () => { el.parentElement.removeChild(el); return el; };
		el.getHTML = () => { return el.innerHTML; };
		el.setHTML = (h) => { el.innerHTML = h; };
		el.setValue = (v) => { el.value = v; };
		el.getValue = () => { return el.value; };
		el.loading = (i) => { el.classList[i ? 'add' : 'remove']('loading'); }
	}

	return (
		elem.length === 1
			? elem[0]
			: elem.length === 0
				? false
				: elem
	);
};