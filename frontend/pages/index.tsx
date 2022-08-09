import * as React from 'react';
import HomeButton from '../components/homeButton';
import Grid from '@mui/material/Grid';

export default function Index() {
  return (
    <Grid container spacing={2} justifyContent="space-around" alignItems="stretch" alignContent="stretch">
      <HomeButton href="/orders/new" color="info">
        Prendre une commande
      </HomeButton>
      <HomeButton href="/orders/created" color="success">
        Commandes à payer (todo)
      </HomeButton>
      <HomeButton href="/preparation/by-products" color="warning">
        Produits à préparer (todo)
      </HomeButton>
    </Grid>
  );
}