<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Agent;
class AgentForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $agent;
    public $template;
    public $subject;
    public $reset_pass_text;
    public function __construct($agent,$template,$subject,$reset_pass_text)
    {
        $this->agent=$agent;
        $this->subject=$subject;
        $this->template=$template;
        $this->reset_pass_text=$reset_pass_text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $agent=$this->agent;
        $template=$this->template;
        $reset_pass_text=$this->reset_pass_text;
        return $this->subject($this->subject)->view('agent.auth.send-forget-token',compact('agent','template','reset_pass_text'));
    }
}
