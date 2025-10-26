import { render, screen } from '@testing-library/react';
import Page from '../app/page';

describe('Home Page', () => {
  it('renders title text', () => {
    render(<Page />);
    expect(screen.getByText('PokeTournament')).toBeInTheDocument();
  });
});


