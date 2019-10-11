import mongoose from 'mongoose';
import shortid from 'shortid';

export interface ITournament extends mongoose.Document {
  uuid: string;
}

const TournamentSchema = new mongoose.Schema({
  uuid: { type: String, index: true, default: (): string => shortid.generate() },
});

const Tournament = mongoose.model<ITournament>('Tournament', TournamentSchema);

export default Tournament;
