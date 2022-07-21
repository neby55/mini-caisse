import * as React from 'react';
import CssBaseline from '@mui/material/CssBaseline';
import Box from '@mui/material/Box';
import Container from '@mui/material/Container';

type Props = {
  children?:
    | React.ReactNode
    | React.ReactNode[];
};

export default function SimpleContainer(props:Props) {
  return (
    <React.Fragment>
      <CssBaseline />
      <Container maxWidth="lg" sx={{my: 3}}>
        {props.children}
      </Container>
    </React.Fragment>
  );
}
