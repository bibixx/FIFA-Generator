import Tournament, { ITournament } from './model';

export const createTournament = async (): Promise<ITournament> => {
  const tournament = new Tournament();

  tournament.save();

  return tournament;
};

export const getTournaments = async (): Promise<ITournament[]> => {
  const tournaments = await Tournament.find({});

  return tournaments;
};

export const getTournamentById = async (uuid: string): Promise<ITournament|null> => {
  const tournament = await Tournament.findOne({ uuid });

  return tournament;
};

export const deleteTournamentById = async (uuid: string): Promise<void> => {
  await Tournament.deleteOne({ uuid });
};
