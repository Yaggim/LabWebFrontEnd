const urlMarcaJSON = '../admin/db/marca.json'; 
const urlModeloJSON = '../admin/db/modelo.json';
const urlCategoriaJSON = '../admin/db/categoria.json';
const urlProductoJSON = '../admin/db/producto.json';
const urlCombosJSON = '../admin/db/combos.json';

export const marca = async () => {

    const response = await fetch(urlMarcaJSON);
    const MarcaJSON = await response.json();
    return MarcaJSON;
};

export const modelo = async () => {

    const response = await fetch(urlModeloJSON);
    const ModeloJSON = await response.json();
    return ModeloJSON;
};

export const categoria = async () => {

    const response = await fetch(urlCategoriaJSON);
    const CategoriaJSON = await response.json();
    return CategoriaJSON;
};

export const producto = async () => {

    const response = await fetch(urlProductoJSON);
    const ProductoJSON = await response.json();
    return ProductoJSON;
};

export const combos = async () => {

    const response = await fetch(urlCombosJSON);
    const CombosJSON = await response.json();
    return CombosJSON;
};