import Tournament, { ITournament } from './model';

export const createTournament = async (name?: string): Promise<ITournament> => {
  const tournament = new Tournament({ name });

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

export const editTournamentById = async (
  uuid: string,
  name: string,
// eslint-disable-next-line no-return-await
): Promise<ITournament|null> => await Tournament.findOneAndUpdate({ uuid }, { name });
