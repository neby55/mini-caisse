import type { AppProps } from 'next/app';
import * as React from 'react';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import ColorModeContext from '../components/themeContext';
import ResponsiveAppBar from '../components/appbar';
import SimpleContainer from '../components/simpleContainer';

function MyApp({ Component, pageProps }: AppProps) {
  const [mode, setMode] = React.useState<'light' | 'dark'>('light');

  const colorMode = {
    themeMode: mode,
    toggleColorMode: () => {
      setMode((prevMode) => (prevMode === 'light' ? 'dark' : 'light'));
    },
  };

  const theme = React.useMemo(
    () =>
      createTheme({
        palette: {
          mode,
        },
      }),
    [mode],
  );

  return (
    <ColorModeContext.Provider value={colorMode}>
      <ThemeProvider theme={theme}>
        <ResponsiveAppBar />
        <SimpleContainer>
          <Component {...pageProps} />
        </SimpleContainer>
      </ThemeProvider>
    </ColorModeContext.Provider>
  );
}

export default MyApp
