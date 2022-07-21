import ResponsiveAppBar from '../../components/appbar';

export default function Preparation({ products }) {
  return (
    <>
      <ResponsiveAppBar />

      {products.map(product => (
        <h3>{product.name}</h3>
      ))}
    </>
  );
}

export async function getStaticProps() {
  const res = await fetch('http://backend/api/products');
  const products = await res.json();
  return {
    props: {
      products,
    },
  }
}
