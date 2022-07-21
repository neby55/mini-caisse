import React, { useEffect } from 'react';
import TextField from '@mui/material/TextField';
import Grid from '@mui/material/Grid';
import Typography from '@mui/material/Typography';
import { Button } from '@mui/material';
import Alert, { AlertColor } from '@mui/material/Alert';

type Product = {
  id: number;
  name: string;
  price: number;
  color: string;
  status: string;
};

type Props = {
  products: Product[];
  children?:
  | React.ReactNode
  | React.ReactNode[];
};

type Cart = {
  product: Product;
  quantity: number;
};

type Order = {
  total: number;
  items: Cart[];
}

type Items = {
  [key: number]: number | string;
}

type AlertMessage = {
  message: string;
  severity: AlertColor;
}

export async function getServerSideProps() {
  // Fetch data from external API
  const res = await fetch('http://backend/api/products');
  const products = await res.json();
  return {
    props: { products },
  }
}

export default function NewOrder({ products }: Props) {
  const [order, setOrder] = React.useState<Order>({
    total: 0,
    items: []
  });
  const [canValidate, setCanValidate] = React.useState<Boolean>(false);
  const [items, setItems] = React.useState<Items>({});
  const [orderNumber, setOrderNumber] = React.useState<Number|String>();
  const [alerts, setAlerts] = React.useState<AlertMessage[]>([]);

  const checkIsNaN = (value: any, itemLabel: string): boolean => {
    if (isNaN(value) !== false) {
      alerts.push({
        message: 'L\'élément "' + itemLabel + '" doit être un nombre',
        severity: 'error'
      });
      setAlerts([...alerts]);
      return false;
    } else if (alerts.length > 0) {
      setAlerts([]);
    }
    return true;
  };

  const deleteAlert = (key: number) => {
    const newAlerts = alerts.filter((alert, alertKey) => alertKey !== key);
    setAlerts(newAlerts);
  };

  const changeProductQuantity = (product: Product, quantity: number) => {
    if (checkIsNaN(quantity, product.name)) {
      // Change item value
      items[product.id] = quantity;
      setItems({...items});

      // Now change order content
      const newOrder = {
        total: 0,
        items: order.items.filter(e => e.product.id !== product.id)
      };
      newOrder.items.push({
        product,
        quantity
      });
      newOrder.total = newOrder.items.reduce((total, item) => total + item.product.price * item.quantity, 0);
      setOrder(newOrder);
      setCanValidate(true);
    }
  };

  const clearItems = () => {
    // Empty item values
    setItems({});

    // Now change order content
    const newOrder = {
      total: 0,
      items: []
    };
    newOrder.total = 0;
    setOrder(newOrder);
    setCanValidate(false);
  };

  const validateOrder = async (evt: Event) => {
    console.log('submit');
    evt.preventDefault();
    if (canValidate) {
      // Call API
      const data = {
        number: orderNumber,
        items: order.items.map(el => { return {id: el.product.id, qty: el.quantity} })
      }
      const response = await fetch('http://localhost:8001/api/orders', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      // If created
      if (response.status === 201) {
        alerts.push({
          message: 'La commande a bien été ajoutée',
          severity: 'success'
        });
        setAlerts([...alerts]);
        // Reinit states
        setCanValidate(false);
        setOrder({
          total: 0,
          items: []
        });
        setItems({});
        setOrderNumber('');
      } else if (response.status === 400) {
        alerts.push({
          message: await response.text(),
          severity: 'error'
        });
        setAlerts([...alerts]);
      }
    }
  };

  return (
    <form action="/api/orders" method="post" onSubmit={validateOrder}>
      <>
      {alerts.length > 0 && alerts.map((alert, key) => (
        <Alert severity={alert.severity} onClose={() => { deleteAlert(key) }} key={key}>{alert.message}</Alert>
      ))}
      </>
      <Grid container spacing={2} sx={{my:2}}>
        <Grid item xs={6} sm={4}>
          <TextField
            required
            label="Numéro"
            id="order-number"
            placeholder='Numéro du ticket'
            variant="outlined"
            focused
            fullWidth
            inputProps={{
              inputMode: 'numeric', pattern: '[0-9]*',
              // startAdornment: <InputAdornment position="end">à {product.price} &euro;</InputAdornment>
            }}
            onChange={(e: Event) => {
              const newOrderNumber = e.currentTarget?.value;
              if (checkIsNaN(newOrderNumber, 'Numéro de commande')) {
                setOrderNumber(parseInt(newOrderNumber));
              }
            }}
            value={orderNumber ?? ''}
          />
        </Grid>
        <Grid item xs={6} sm={4} textAlign="center">
          <Typography fontSize={36} fontWeight='bold'>{order.total} &euro;</Typography>
        </Grid>
        <Grid item xs={12} sm={4} alignSelf="center">
        {canValidate &&
          <Grid container spacing={1}>
            <Grid item xs={6} sm={6}>
              <Button variant="contained" size="large" color="info" fullWidth onClick={clearItems}>Reset</Button>
            </Grid>
            <Grid item xs={6} sm={6}>
              <Button variant="contained" size="large" color="success" fullWidth type='submit'>Valider</Button>
            </Grid>
          </Grid>
        }
        {!canValidate &&
          <Typography align='center'>Veuillez saisir le numéro de commande et au moins une quantité</Typography>
        }
        </Grid>
      </Grid>

      <Grid container spacing={2} sx={{my:2}}>
        {products.map(product => (
          <Grid item xs={6} sm={4} key={product.id} sx={{
            flex: '1 1 auto'
          }}>
            <TextField
              fullWidth
              label={product.name}
              id={'qte-' + product.id}
              placeholder={'Quantité de ' + product.name}
              inputProps={{
                inputMode: 'numeric', pattern: '[0-9]*',
                // startAdornment: <InputAdornment position="end">à {product.price} &euro;</InputAdornment>
              }}
              sx={{
                backgroundColor: `${product.color}22`,
                '& label.Mui-focused': {
                  color: product.color
                },
                '& .MuiOutlinedInput-root': {
                  '& fieldset': {
                    borderColor: product.color
                  },
                  '&.Mui-focused fieldset': {
                    borderColor: product.color
                  }
                }
              }}
              onChange={(e: Event) => {
                changeProductQuantity(product, e.currentTarget.value);
              }}
              value={items[product.id] ?? ''}
            />
            <Typography variant="caption" textAlign="right">à {product.price} &euro;</Typography>
          </Grid>
        ))}
      </Grid>
    </form>
  );
}
