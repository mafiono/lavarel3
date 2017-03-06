export default class
{
    constructor(data)
    {
        this.data = Object.assign({}, data);
    }

    set(key, data)
    {
        return this.data[key] = data;
    }

    get(key)
    {
        return this.data[key];
    }

    remove(key)
    {
        delete this.data[key];
    }

    getData()
    {
        return this.data;
    }
}