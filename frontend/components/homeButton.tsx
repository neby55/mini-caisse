import * as React from 'react';
import Link from 'next/link';
import Button from '@mui/material/Button';
import Grid from '@mui/material/Grid';
import { Url } from 'url';

type Props = {
  href: Url | String;
  color: String;
  children: String;
};

export default function HomeButton(props: Props) {
  return (
    <Grid item sx={{ flex: "1 1 auto" }}>
      <Link href={props.href} passHref>
        <Button variant="contained" color={props.color} sx={{
          width: "100%",
          height: "100%",
          fontSize: "3em"
        }}>
          {props.children}
        </Button>
      </Link>
    </Grid>
  );
}